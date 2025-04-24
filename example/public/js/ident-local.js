function getUserAgent() {
  return navigator.userAgent
}

function getScreenResolution() {
  return `${window.screen.width}x${window.screen.height}`
}

function getTimeZone() {
  return Intl.DateTimeFormat().resolvedOptions().timeZone
}

function getOnlineStatus() {
  return navigator.onLine ? "Online" : "Offline"
}

function getLanguage() {
  return navigator.language
}

function getPlatform() {
  return navigator.platform
}

function getDoNotTrackStatus() {
  return navigator.doNotTrack || "N/A"
}

function getHardwareConcurrencyStatus() {
  return navigator.hardwareConcurrency.toString() || "N/A"
}

function getCookiesStatus() {
  return navigator.cookieEnabled ? "Cookies Enabled" : "Cookies Disabled"
}

function getPlugins() {
  let plugins = Array.from(navigator.plugins).map(plugin => plugin.name)
  return [...new Set(plugins)].join(";")
}

function getTouchSupport() {
  return "ontouchstart" in window || navigator.maxTouchPoints > 0
    ? "Touch Supported"
    : "Touch Not Supported"
}

function computeHashBuffer(buffer) {
  const view = new DataView(buffer)
  let hash = 0
  for (let i = 0; i < view.byteLength; i++) {
    hash = (hash << 5) - hash + view.getUint8(i)
  }
  return hash.toString(16)
}

async function computeSha256(input) {
  const utf8 = new TextEncoder().encode(input)
  const hashBuffer = await crypto.subtle.digest("SHA-256", utf8)
  const hashArray = Array.from(new Uint8Array(hashBuffer))
  const hashHex = hashArray
    .map(byte => byte.toString(16).padStart(2, "0"))
    .join("")
  return hashHex
}

async function getFonts() {
  if ("fonts" in document) {
    try {
      await document.fonts.ready
      const fontList = Array.from(document.fonts.values()).map(
        font => font.family
      )
      const distinctFonts = [...new Set(fontList)]
      const fontListString = distinctFonts.join(";")
      return fontListString || "N/A"
    } catch (error) {
      return "N/A"
    }
  } else {
    return "N/A"
  }
}

async function getWebGLFingerprint() {
  const gl = document.createElement("canvas").getContext("webgl")
  if (!gl) return "N/A"

  const debugInfo = gl.getExtension("WEBGL_debug_renderer_info")
  if (!debugInfo) {
    return "N/A"
  }
  const renderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL) || "N/A"
  const vendor = gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL) || "N/A"
  return `${renderer}${vendor}`
}

async function getAudioFingerprint() {
  try {
    const audioContext = new AudioContext()
    const oscillator = audioContext.createOscillator()
    oscillator.type = "triangle"
    oscillator.frequency.setValueAtTime(10000, audioContext.currentTime)
    const analyser = audioContext.createAnalyser()
    const fingerprintArray = new Uint8Array(analyser.frequencyBinCount)

    oscillator.connect(analyser)
    analyser.connect(audioContext.destination)
    oscillator.start()
    analyser.getByteFrequencyData(fingerprintArray)
    oscillator.stop()

    const audioFingerprint = Array.from(fingerprintArray).join(",")

    return audioFingerprint
  } catch (error) {
    return "N/A"
  }
}

async function getCanvasFingerprint() {
  return new Promise(resolve => {
    const canvas = document.createElement("canvas")
    const ctx = canvas.getContext("2d")
    const text = "CanvasFingerprint"

    ctx.textBaseline = "top"
    ctx.font = '14px "Arial"'
    ctx.textBaseline = "alphabetic"
    ctx.fillStyle = "#f60"
    ctx.fillRect(125, 1, 62, 20)
    ctx.fillStyle = "#069"
    ctx.fillText(text, 2, 15)
    ctx.fillStyle = "rgba(102, 204, 0, 0.7)"
    ctx.fillText(text, 4, 17)

    canvas.toBlob(async blob => {
      const buffer = await blob.arrayBuffer()
      const canvasFingerprint = computeHashBuffer(buffer)
      resolve(canvasFingerprint)
    }, "image/png")
  })
}

async function getBrowserFingerprint(ipAddress = false) {
  let userAgent = getUserAgent()
  let screenResolution = getScreenResolution()
  let timeZone = getTimeZone()
  let online = getOnlineStatus()
  let language = getLanguage()
  let platform = getPlatform()
  let track = getDoNotTrackStatus()
  let hardwareConcurrency = getHardwareConcurrencyStatus()
  let cookies = getCookiesStatus()
  let touch = getTouchSupport()
  let plugins = getPlugins()
  let fonts = await getFonts()
  let canvas = await getCanvasFingerprint()
  let webgl = await getWebGLFingerprint()
  let audio = getAudioFingerprint()
  let raw = `${userAgent}-${screenResolution}-${timeZone}-${online}-${language}-${platform}-${track}-${hardwareConcurrency}-${cookies}-${touch}-${plugins}-${fonts}-${canvas}-${webgl}-${audio}`
  let fingerprint = await computeSha256(raw)
  return fingerprint
}

function client_ID(dest, token) {
  this.getBrowserFingerprint()
  .then(fp => {
    var xhr = new XMLHttpRequest();
    var url = dest + '/' + fp;
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Ajax-Request-Token', token);
    xhr.send();
  });
}

function LimitFilesize(src, size) {
  if( src.files[0].size > size ) {
    alert('invalid file size');
    src.value = "";
  }
}