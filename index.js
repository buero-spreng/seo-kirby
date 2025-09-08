(function() {
  "use strict";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main = {
    props: {
      layout: String,
      size: String
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-panel-inside", [_c("k-view", { staticClass: "k-seo-view" }, [_c("k-header", [_vm._v("SEO Einstellungen")]), _c("k-input", { attrs: { "label": "Seitentitel", "name": "seoTitle", "type": "text", "value": _vm.value }, on: { "change": function($event) {
      return _vm.$emit("update:value", $event);
    } } })], 1)], 1);
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns
  );
  __component__.options.__file = "/Volumes/Daten Alle/Kunden/sven spreng/Allgemeines/Development/sandbox/18_test-seo-plugin/test-seo-plugin/site/plugins/seo-kirby/src/components/SeoFields.vue";
  const SeoFields = __component__.exports;
  const SeoView = {
    props: {
      fields: { type: Object, required: true },
      values: { type: Object, required: true },
      language: { type: String, default: null }
    },
    data() {
      return {
        form: { ...this.values },
        saving: false,
        error: null
      };
    },
    methods: {
      async save() {
        this.saving = true;
        this.error = null;
        try {
          await this.$api.patch("site", {
            ...this.form,
            ...this.language ? { language: this.language } : {}
          });
          this.$store.notification.success(this.$t("saved"));
        } catch (e) {
          this.error = e;
          this.$store.notification.error(e.message || "Save failed");
        } finally {
          this.saving = false;
        }
      }
    },
    template: SeoFields
  };
  window.panel.plugin("kesabr/seo-kirby", {
    components: { seo: SeoView }
  });
})();
