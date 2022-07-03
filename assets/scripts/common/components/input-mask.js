import IMask from 'imask';

export const bindInputMask = (input) => {
    IMask(input, {
        mask: input.dataset.maskInput || '',
    });
};
