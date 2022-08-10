import { bindInputMask } from './components/input-mask';
import { bindConfirmForm } from './components/confirm';
import { bindBulmaFile } from './components/bulma-file';

const mapping = {
  '[data-mask-input]': bindInputMask,
  '[data-input-file]': bindBulmaFile,
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
