import { useEffect } from 'react'
import { Routes, Route, Navigate } from 'react-router-dom'
import { useDispatch, useSelector } from 'react-redux'
import LoginPage from './pages/LoginPage'
import InboxPage from './pages/InboxPage'
import BroadcastPage from './pages/BroadcastPage'
import AnalyticsPage from './pages/AnalyticsPage'
import SettingsPage from './pages/SettingsPage'
import { fetchUser } from './store/slices/authSlice'

function App() {
  const dispatch = useDispatch()
  const { user, isAuthenticated, loading } = useSelector((state) => state.auth)

  useEffect(() => {
    const token = localStorage.getItem('token')
    if (token && !user) {
      dispatch(fetchUser())
    }
  }, [dispatch, user])

  if (loading) {
    return (
      <div className="h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    )
  }

  return (
    <Routes>
      <Route path="/login" element={!isAuthenticated ? <LoginPage /> : <Navigate to="/" />} />
      <Route path="/" element={isAuthenticated ? <InboxPage /> : <Navigate to="/login" />} />
      <Route path="/broadcast" element={isAuthenticated ? <BroadcastPage /> : <Navigate to="/login" />} />
      <Route path="/analytics" element={isAuthenticated ? <AnalyticsPage /> : <Navigate to="/login" />} />
      <Route path="/settings" element={isAuthenticated ? <SettingsPage /> : <Navigate to="/login" />} />
    </Routes>
  )
}

export default App
