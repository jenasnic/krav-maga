import { bindInputMask } from './components/input-mask';

const mapping = {
  '[data-mask-input]': bindInputMask,
};

export const bindForm = (element) => {
  Object.entries(mapping).forEach(([selector, binder]) => {
    [...(element || document).querySelectorAll(selector)].forEach((element) => {
      binder(element);
    }
  )});
};

bindForm();
