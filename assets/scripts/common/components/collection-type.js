import { bindForm } from '../form';

/**
 * Allows to manage CollectionType in a form with add/remove functionalities.
 * HTML tag can be used to wrap new element in case of creation using data attribute 'collectionType' (e.g. data-collection-type="li").
 * When allowing add, we use both data attributes 'prototype' and 'prototypeName' (@see https://symfony.com/doc/current/reference/forms/types/collection.html)
 */
export class CollectionType {
    referenceIndex = 0;
    options = {};
    items = {};

    constructor(options) {
        this.referenceIndex = options.collection.querySelectorAll('.collection-type-item').length;
        this.options = options;

        if (this.options.collection.dataset.triggerAdd) {
            this.initializeTriggerAdd();
        }

        if (this.options.collection.dataset.triggerRemove) {
            this.options.collection.querySelectorAll('.collection-type-item').forEach(item => {
                this.addDeleteButton(item);
            });
        }
    }

    initializeTriggerAdd() {
        this.options.trigger = this.options.collection.querySelector('.add-item');
        this.options.trigger.addEventListener('click', (evt) => {evt.preventDefault(); this.addItem();});
    }

    addItem() {
        const collection = this.options.collection;
        const prototypeName = this.options.collection.dataset.prototypeName;

        let newItem;

        if (collection.dataset.collectionType) {
            newItem = document.createElement(collection.dataset.collectionType);
            newItem.classList.add('collection-type-item');
            newItem.innerHTML = collection.dataset.prototype.replaceAll(prototypeName, ++this.referenceIndex);
        } else {
            const template = document.createElement('template');
            template.innerHTML = collection.dataset.prototype.replaceAll(prototypeName, ++this.referenceIndex).trim();
            newItem = template.content.firstChild;
        }

        if (collection.children.length > 1) {
          const addButton = collection.children[1];
          collection.insertBefore(newItem, addButton);
        } else {
          collection.append(newItem);
        }

        bindForm(newItem);

        if (this.options.collection.dataset.triggerRemove) {
            this.addDeleteButton(newItem);
        }
    }

    deleteItem(item) {
        item.remove();
    }

    addDeleteButton(item) {
        const removeButton = document.createElement('span');
        removeButton.classList.add('button', 'is-small', 'is-danger', 'remove-item');
        removeButton.title = this.options.collection.dataset.triggerRemove;
        removeButton.innerHTML = '<i class="icon-cross"></i>';

        item.prepend(removeButton);

        removeButton.addEventListener('click', (evt) => {
            evt.preventDefault();
            if (this.isConfirmed()) {
                this.deleteItem(item);
            }
        });
    }

    isConfirmed() {
        const message = this.options.collection.dataset.triggerRemoveConfirm || null;
        if (null === message) {
            return true;
        }

        return confirm(message);
    }
}

export const bindCollectionType = (element) => {
  new CollectionType({
    collection: element,
  });
};
