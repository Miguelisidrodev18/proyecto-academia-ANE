import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/* ── Componente: búsqueda de alumnos para matrículas ── */
Alpine.data('alumnoSearch', () => ({
    open:      false,
    query:     '',
    loading:   false,
    checking:  false,
    results:   [],
    selected:  { id: '', nombre: '', dni: '', tipo: '', inicial: '' },
    advertencia: null,
    confirmar: false,
    _timer:    null,
    urlBuscar: '',
    urlBase:   '',

    init() {
        this.urlBuscar = this.$el.dataset.urlBuscar || '';
        this.urlBase   = this.$el.dataset.urlBase   || '';

        const initId    = this.$el.dataset.initialId    || '';
        const initLabel = this.$el.dataset.initialLabel || '';
        if (initId) {
            this.selected = {
                id:      initId,
                nombre:  initLabel,
                dni:     '',
                tipo:    '',
                inicial: initLabel.charAt(0).toUpperCase(),
            };
        }

        this.$watch('query', () => {
            clearTimeout(this._timer);
            this._timer = setTimeout(() => this.doFetch(), 280);
        });

        this.$watch('confirmar', (val) => {
            if (this.advertencia) {
                this.$dispatch('bloquear-submit', { bloqueado: !val });
            }
        });
    },

    doFetch() {
        const self = this;
        self.loading = true;
        self.open    = true;

        fetch(self.urlBuscar + '?q=' + encodeURIComponent(self.query) + '&_=' + Date.now(), {
            credentials: 'same-origin',
            headers: {
                'Accept':           'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function (data) {
            self.results = Array.isArray(data) ? data : [];
            self.loading = false;
        })
        .catch(function (e) {
            console.error('[alumnoSearch] error:', e);
            self.results = [];
            self.loading = false;
        });
    },

    toggle() {
        this.open = !this.open;
        if (this.open) this.doFetch();
    },

    pick(opt) {
        const self = this;
        self.selected    = opt;
        self.open        = false;
        self.query       = '';
        self.advertencia = null;
        self.confirmar   = false;
        self.$dispatch('bloquear-submit', { bloqueado: false });

        self.checking = true;
        fetch(self.urlBase + '/' + opt.id + '/matriculas-activas', {
            credentials: 'same-origin',
            headers: {
                'Accept':           'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            self.checking = false;
            if (data.tieneActiva) {
                self.advertencia = data.matricula;
                self.$dispatch('bloquear-submit', { bloqueado: true });
            }
        })
        .catch(function () { self.checking = false; });
    },
}));

/* ── Componente: gestión de foto de perfil ── */
Alpine.data('avatarManager', (config) => ({
    showModal:  false,
    step:       'upload',   // 'upload' | 'crop' | 'success'
    rawImage:   null,
    cropper:    null,
    uploading:  false,
    deleting:   false,
    dragover:   false,
    errorMsg:   null,
    currentAvatar: config.currentAvatar || '',
    uploadUrl:  config.uploadUrl  || '',
    deleteUrl:  config.deleteUrl  || '',
    csrfToken:  config.csrfToken  || '',
    msgIndex:   0,
    messages: [
        'Completa tu perfil y destaca en la academia 🚀',
        'Tu foto ayuda a mejorar la experiencia académica ✨',
        'Personaliza tu identidad digital en la academia 🎯',
    ],

    init() {
        setInterval(() => { this.msgIndex = (this.msgIndex + 1) % this.messages.length; }, 4000);
    },

    get currentMessage() {
        return this.messages[this.msgIndex];
    },

    openModal() {
        this.showModal = true;
        this.step      = 'upload';
        this.rawImage  = null;
        this.errorMsg  = null;
        if (this.cropper) { this.cropper.destroy(); this.cropper = null; }
    },

    closeModal() {
        this.showModal = false;
        if (this.cropper) { this.cropper.destroy(); this.cropper = null; }
        this.rawImage = null;
        this.step     = 'upload';
        this.errorMsg = null;
    },

    handleDrop(e) {
        this.dragover = false;
        const file = e.dataTransfer.files[0];
        if (file) this.loadFile(file);
    },

    handleFileInput(e) {
        const file = e.target.files[0];
        if (file) this.loadFile(file);
    },

    loadFile(file) {
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowed.includes(file.type)) {
            this.errorMsg = 'Formato no válido. Usa JPG, PNG o WebP.';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            this.errorMsg = 'La imagen no puede superar los 5 MB.';
            return;
        }
        this.errorMsg = null;
        const reader  = new FileReader();
        reader.onload = (e) => {
            this.rawImage = e.target.result;
            this.step     = 'crop';
            this.$nextTick(() => this.initCropper());
        };
        reader.readAsDataURL(file);
    },

    initCropper() {
        const img = this.$refs.cropperImage;
        if (!img || typeof Cropper === 'undefined') return;
        this.cropper = new Cropper(img, {
            aspectRatio:        1,
            viewMode:           2,
            dragMode:           'move',
            autoCropArea:       0.9,
            responsive:         true,
            restore:            false,
            guides:             false,
            center:             false,
            highlight:          false,
            cropBoxMovable:     false,
            cropBoxResizable:   false,
            toggleDragModeOnDblclick: false,
        });
    },

    async cropAndUpload() {
        if (!this.cropper) return;
        this.uploading = true;
        this.errorMsg  = null;

        const canvas = this.cropper.getCroppedCanvas({
            width:                 400,
            height:                400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(async (blob) => {
            const formData = new FormData();
            formData.append('avatar', blob, 'avatar.jpg');
            formData.append('_token', this.csrfToken);

            try {
                const res  = await fetch(this.uploadUrl, { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    const url = data.avatar_url + '?t=' + Date.now();
                    this.currentAvatar = url;
                    document.querySelectorAll('.js-user-avatar').forEach(el => el.src = url);
                    this.step = 'success';
                    setTimeout(() => { this.closeModal(); window.location.reload(); }, 2200);
                } else {
                    this.errorMsg = data.message || 'Error al subir la imagen.';
                }
            } catch {
                this.errorMsg = 'Error de red. Intenta nuevamente.';
            } finally {
                this.uploading = false;
            }
        }, 'image/jpeg', 0.92);
    },

    async deleteAvatar() {
        if (!confirm('¿Eliminar tu foto de perfil? Se restaurará el avatar predeterminado.')) return;
        this.deleting = true;

        try {
            const res  = await fetch(this.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Content-Type': 'application/json',
                },
            });
            const data = await res.json();
            if (data.success) {
                const url = data.avatar_url + '?t=' + Date.now();
                this.currentAvatar = url;
                document.querySelectorAll('.js-user-avatar').forEach(el => el.src = url);
                window.location.reload();
            }
        } catch {
            alert('Error al eliminar. Intenta nuevamente.');
        } finally {
            this.deleting = false;
        }
    },
}));

Alpine.start();
