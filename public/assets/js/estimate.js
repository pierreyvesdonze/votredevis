document
    .querySelectorAll('ul.tags li')
    .forEach((tag) => {
        addTagFormDeleteLink(tag)
    })

const addTagLink = document.createElement('a')
addTagLink.classList.add('custom-btn')
addTagLink.classList.add('add-estimate-line-btn')
addTagLink.href = '#'
addTagLink.innerText = 'Ajouter une ligne'
addTagLink.dataset.collectionHolderClass = 'tags'

const newLinkLi = document.createElement('li').append(addTagLink)

const collectionHolder = document.querySelector('ul.tags')
collectionHolder.appendChild(addTagLink)

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item);
}

const addTagFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer cette ligne';
    removeFormButton.classList.add('custom-btn');

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}

addTagLink.addEventListener("click", addFormToCollection)

