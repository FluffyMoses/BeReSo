// Bereso
// BEst REcipe SOftware
// ###################################
// wake screen lock
// included by ../main.html (if enabled)
// ###################################


if ('wakeLock' in navigator) {
    console.log('wakeLock: enabled and supported');
  // create a reference for the wake lock
  let wakeLock = null;

  // create an async function to request a wake lock
  const requestWakeLock = async () => {
    try {
      wakeLock = await navigator.wakeLock.request('screen');
    } catch (err) {
      // if wake lock request fails - usually system related, such as battery
      console.log(`${err.name}, ${err.message}`);
    }
  } 

  requestWakeLock();
} else {
    console.log('wakeLock: enabled but NOT supported');
}
