import imgSelectModal from './imgModal.vue';

const imgModal = {};

imgModal.install = function (Vue) {
  let instance = null;
  Vue.prototype.$imgModal = function (callback, isMore, modelName, boolean) {
    if (!instance) {
      const ModalConstructor = Vue.extend(imgSelectModal);
      instance = new ModalConstructor({ parent: this.$root });
      instance.$mount(document.createElement('div'));
      document.body.appendChild(instance.$el);
    }
    instance.visible = true;
    instance.callback = callback;
    instance.more = isMore;
    instance.modelName = modelName;
    instance.booleanVal = boolean;
  };
};

export default imgModal;
