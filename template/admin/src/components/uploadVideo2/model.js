import videoSelectModal from './videoModal.vue';

const videoModal = {};

videoModal.install = function (Vue) {
  let instance = null;
  Vue.prototype.$videoModal = function (callback, isMore, modelName, boolean) {
    if (!instance) {
      const ModalConstructor = Vue.extend(videoSelectModal);
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

export default videoModal;
