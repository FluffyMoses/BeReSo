// Bereso
// BEst REcipe SOftware
// ###################################
// service Worker register
// included by ../main.html
// ###################################

if ('serviceWorker' in navigator) {
    console.log('serviceWorker: supported by browser')
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('service_worker.js')
            .then(reg => console.log('serviceWorker: registered'))
            .catch(err => console.log(`serviceWorker: Error - ${err}`))
    })
}
