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

Alpine.start();
