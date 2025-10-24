import { FiSettings, FiUsers, FiKey, FiDatabase } from 'react-icons/fi'
import Sidebar from '../components/Sidebar/Sidebar'

function SettingsPage() {
  return (
    <div className="flex h-screen bg-gray-50">
      <Sidebar />

      <div className="flex-1 overflow-y-auto">
        <div className="max-w-5xl mx-auto p-8">
          {/* Header */}
          <div className="mb-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-2">Settings</h1>
            <p className="text-gray-600">Manage system configuration and preferences</p>
          </div>

          <div className="space-y-6">
            {/* General Settings */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div className="flex items-center space-x-3 mb-4">
                <FiSettings className="w-5 h-5 text-primary-600" />
                <h2 className="text-xl font-semibold text-gray-900">General Settings</h2>
              </div>
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    System Name
                  </label>
                  <input
                    type="text"
                    defaultValue="TIMS"
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Timezone
                  </label>
                  <select className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option>UTC</option>
                    <option>Asia/Kuala_Lumpur</option>
                    <option>Asia/Singapore</option>
                  </select>
                </div>
              </div>
            </div>

            {/* User Management */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div className="flex items-center space-x-3 mb-4">
                <FiUsers className="w-5 h-5 text-primary-600" />
                <h2 className="text-xl font-semibold text-gray-900">User Management</h2>
              </div>
              <p className="text-gray-600 mb-4">
                Manage users, roles, and permissions for your team.
              </p>
              <button className="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                Manage Users
              </button>
            </div>

            {/* WhatsApp Configuration */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div className="flex items-center space-x-3 mb-4">
                <FiKey className="w-5 h-5 text-primary-600" />
                <h2 className="text-xl font-semibold text-gray-900">WhatsApp API</h2>
              </div>
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number ID
                  </label>
                  <input
                    type="text"
                    placeholder="Enter WhatsApp Phone ID"
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Access Token
                  </label>
                  <input
                    type="password"
                    placeholder="Enter Access Token"
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                  />
                </div>
              </div>
            </div>

            {/* Database & Archival */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div className="flex items-center space-x-3 mb-4">
                <FiDatabase className="w-5 h-5 text-primary-600" />
                <h2 className="text-xl font-semibold text-gray-900">Database & Archival</h2>
              </div>
              <p className="text-gray-600 mb-4">
                Configure data retention and archival policies.
              </p>
              <div className="flex items-center space-x-3">
                <button className="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                  View Archive
                </button>
                <button className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                  Run Cleanup
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default SettingsPage
