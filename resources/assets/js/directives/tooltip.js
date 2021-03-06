import Vue from "vue";

Vue.directive("tooltip", {
    bind: function (el, binding) {
        var $el = $(el);

        $el.tooltip({
            animation: $el.data("animation") || true,
            container: $el.data("container") || false,
            html: $el.data("html") || false,
            placement: $el.data("placement") || 'top',
            title: binding.expression || '',
            trigger: $el.data("trigger") || 'hover focus',
        });
    },
    unbind: function (el) {
        $(el).tooltip('destroy');
    }
});