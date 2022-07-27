import { bindInputMask } from './components/input-mask';
import { bindConfirmForm } from './components/confirm';

const mapping = {
  '[data-mask-input]': bindInputMask,
  'form[data-confirm]': bindConfirmForm,
};

export const bindForm = (element) => {
  Object.entries(mapping).forEach(([selector, binder]) => {
    [...(element || document).querySelectorAll(selector)].forEach((element) => {
      binder(element);
    }
  )});
};

bindForm();
