import { bindForm } from '../form';

/**
 * Allows to update one or more elements depending on another one using Symfony form validation.
 * Use 'data-fields-update' property with identifier of html wrapper to add form element.
 * NOTE : Implement Symfony using FormEvents to set data...
 *
 * Sample :
 *  <div id="{{ form.vars.id }}">
 *      {{ form_row(form.addMoreInfos, {'attr': {'data-field-update': 'fieldsWrapper'}}) }}
 *      <div data-field-update-target="fieldsWrapper">
 *          {{ form_row(form.additionalInfos) }}
 *      </div>
 *  </div>
 *
 * @param input
 */
export const bindFormFieldUpdate = (input) => {
    // $(input).on('change', () => refreshData(input));
    input.addEventListener('change', () => refreshData(input));
};

const refreshData = async (element) => {
  const formToSubmit = element.closest('form');
  const fieldUpdate = element.dataset.fieldUpdate;
  const wrappers = document.querySelectorAll(`[data-field-update-target="${fieldUpdate}"]`);

  const formData = new FormData(formToSubmit);
  formData.append('_method', 'PATCH');

  // NOTE : fix to send data to FormType when uncheck (required to include 'false' in 'false_values' option for CheckboxType)
  // @see https://symfony.com/doc/current/reference/forms/types/checkbox.html#false-values
  if ('checkbox' === element.type && !element.checked) {
    formData.append(element.name, 'false');
  }

  const response = await fetch(formToSubmit.action, {
    credentials: 'same-origin',
    method: 'POST',
    body: formData,
  });

  const html = await response.text();
  const resultElements = document.createRange().createContextualFragment(html).querySelectorAll(`[data-field-update-target="${fieldUpdate}"]`);

  [...wrappers].forEach((wrapper, key) => {
    const element = resultElements[key];
    if (wrapper.id === element.id) {
      wrapper.innerHTML = element.innerHTML;
      bindForm(wrapper);
    }
  });
};
