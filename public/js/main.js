const form = document.querySelector('form#find-range')
const message = document.querySelector('#message')

form.addEventListener('submit', async (e) => {
    e.preventDefault()
    hideMessage()
    const number = form.number.value
    if (!number) {
        return
    }
    const response = await fetch(`http://localhost:1234/api/scope/period/${number}`)
    const scopes = await response.json()
    if (false === response.ok) {
        errorMessage(scopes?.error?.message || 'Непредвиденная ошибка сервера')
        return;
    }

    if (!scopes?.length) {
        errorMessage('Не найдено')
        return
    }

    const successMsg = scopes.map(scope => scope.id).join(',')
    successMessage(`id: ${successMsg}`)
})

const successMessage = msg => {
    message.innerHTML = (`<div class="alert alert-success">${msg}</div>`)
    showMessage()
}

const errorMessage = msg => {
    message.innerHTML = (`<div class="alert alert-danger">${msg}</div>`)
    showMessage()
}

const hideMessage = () => {
    message.style.display = 'none'
}

const showMessage = () => {
    message.style.display = 'block'
}
