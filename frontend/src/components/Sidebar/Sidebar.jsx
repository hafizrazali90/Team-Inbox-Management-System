import { NavLink, useNavigate } from 'react-router-dom'
import { useDispatch, useSelector } from 'react-redux'
import { FiInbox, FiRadio, FiBarChart2, FiSettings, FiLogOut, FiUser } from 'react-icons/fi'
import { logout } from '../../store/slices/authSlice'
import { toast } from 'react-toastify'

function Sidebar() {
  const dispatch = useDispatch()
  const navigate = useNavigate()
  const { user } = useSelector((state) => state.auth)

  const handleLogout = async () => {
    await dispatch(logout())
    toast.success('Logged out successfully')
    navigate('/login')
  }

  const navItems = [
    { path: '/', icon: FiInbox, label: 'Inbox', roles: ['all'] },
    { path: '/broadcast', icon: FiRadio, label: 'Broadcast', roles: ['admin', 'operation_manager', 'manager'] },
    { path: '/analytics', icon: FiBarChart2, label: 'Analytics', roles: ['admin', 'operation_manager', 'manager'] },
    { path: '/settings', icon: FiSettings, label: 'Settings', roles: ['admin'] },
  ]

  const canAccessRoute = (roles) => {
    if (roles.includes('all')) return true
    return roles.includes(user?.role?.slug)
  }

  return (
    <div className="w-64 bg-white border-r border-gray-200 flex flex-col h-screen">
      {/* Logo */}
      <div className="p-6 border-b border-gray-200">
        <div className="flex items-center space-x-3">
          <div className="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
            <span className="text-xl font-bold text-white">T</span>
          </div>
          <div>
            <h1 className="text-xl font-bold text-gray-900">TIMS</h1>
            <p className="text-xs text-gray-500">{user?.department?.name || 'CX'}</p>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <nav className="flex-1 p-4 space-y-1">
        {navItems.map((item) => {
          if (!canAccessRoute(item.roles)) return null

          return (
            <NavLink
              key={item.path}
              to={item.path}
              className={({ isActive }) =>
                `flex items-center space-x-3 px-4 py-3 rounded-lg transition ${
                  isActive
                    ? 'bg-primary-50 text-primary-700 font-medium'
                    : 'text-gray-700 hover:bg-gray-50'
                }`
              }
            >
              <item.icon className="w-5 h-5" />
              <span>{item.label}</span>
            </NavLink>
          )
        })}
      </nav>

      {/* User Profile */}
      <div className="p-4 border-t border-gray-200">
        <div className="flex items-center space-x-3 mb-3">
          <div className="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
            <FiUser className="w-5 h-5 text-gray-600" />
          </div>
          <div className="flex-1 min-w-0">
            <p className="text-sm font-medium text-gray-900 truncate">{user?.name}</p>
            <p className="text-xs text-gray-500 truncate">{user?.role?.name}</p>
          </div>
        </div>
        <button
          onClick={handleLogout}
          className="w-full flex items-center justify-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition"
        >
          <FiLogOut className="w-4 h-4" />
          <span>Logout</span>
        </button>
      </div>
    </div>
  )
}

export default Sidebar
