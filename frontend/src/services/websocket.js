import Pusher from 'pusher-js'
import Echo from 'laravel-echo'

let echoInstance = null

export const initializeWebSocket = (token) => {
  if (echoInstance) {
    return echoInstance
  }

  window.Pusher = Pusher

  echoInstance = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_WS_KEY || 'tims-key',
    wsHost: import.meta.env.VITE_WS_HOST || 'localhost',
    wsPort: import.meta.env.VITE_WS_PORT || 6001,
    wssPort: import.meta.env.VITE_WS_PORT || 6001,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    cluster: import.meta.env.VITE_WS_CLUSTER || 'mt1',
    authEndpoint: `${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/broadcasting/auth`,
    auth: {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    },
  })

  return echoInstance
}

export const disconnectWebSocket = () => {
  if (echoInstance) {
    echoInstance.disconnect()
    echoInstance = null
  }
}

export const getEcho = () => echoInstance
