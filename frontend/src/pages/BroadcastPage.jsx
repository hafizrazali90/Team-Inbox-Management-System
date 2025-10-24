import { useState } from 'react'
import { FiUpload, FiSend, FiList } from 'react-icons/fi'
import Sidebar from '../components/Sidebar/Sidebar'
import { toast } from 'react-toastify'
import api from '../services/api'

function BroadcastPage() {
  const [name, setName] = useState('')
  const [message, setMessage] = useState('')
  const [recipientType, setRecipientType] = useState('csv')
  const [csvFile, setCsvFile] = useState(null)
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e) => {
    e.preventDefault()
    setLoading(true)

    try {
      const formData = new FormData()
      formData.append('name', name)
      formData.append('message_content', message)
      formData.append('recipient_type', recipientType)
      if (csvFile) formData.append('csv_file', csvFile)

      await api.post('/broadcasts', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })

      toast.success('Broadcast created successfully!')
      setName('')
      setMessage('')
      setCsvFile(null)
    } catch (error) {
      toast.error('Failed to create broadcast')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="flex h-screen bg-gray-50">
      <Sidebar />

      <div className="flex-1 overflow-y-auto">
        <div className="max-w-4xl mx-auto p-8">
          {/* Header */}
          <div className="mb-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-2">Broadcast Messages</h1>
            <p className="text-gray-600">Send messages to multiple recipients at once</p>
          </div>

          {/* Create Broadcast Form */}
          <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-6">Create New Broadcast</h2>

            <form onSubmit={handleSubmit} className="space-y-6">
              {/* Campaign Name */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Campaign Name
                </label>
                <input
                  type="text"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  required
                  placeholder="e.g., Weekly Newsletter"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>

              {/* Recipient Type */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Recipient Type
                </label>
                <div className="grid grid-cols-3 gap-3">
                  {['csv', 'tag', 'individual'].map((type) => (
                    <button
                      key={type}
                      type="button"
                      onClick={() => setRecipientType(type)}
                      className={`px-4 py-3 rounded-lg border-2 transition ${
                        recipientType === type
                          ? 'border-primary-600 bg-primary-50 text-primary-700'
                          : 'border-gray-200 hover:border-gray-300'
                      }`}
                    >
                      {type.charAt(0).toUpperCase() + type.slice(1)}
                    </button>
                  ))}
                </div>
              </div>

              {/* CSV Upload */}
              {recipientType === 'csv' && (
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Upload CSV File
                  </label>
                  <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <FiUpload className="w-12 h-12 text-gray-400 mx-auto mb-3" />
                    <input
                      type="file"
                      accept=".csv"
                      onChange={(e) => setCsvFile(e.target.files[0])}
                      className="hidden"
                      id="csv-upload"
                    />
                    <label
                      htmlFor="csv-upload"
                      className="cursor-pointer text-primary-600 hover:text-primary-700 font-medium"
                    >
                      Click to upload CSV
                    </label>
                    <p className="text-sm text-gray-500 mt-2">
                      Format: phone_number, contact_name
                    </p>
                    {csvFile && (
                      <p className="text-sm text-green-600 mt-2">
                        Selected: {csvFile.name}
                      </p>
                    )}
                  </div>
                </div>
              )}

              {/* Message Content */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Message Content
                </label>
                <textarea
                  value={message}
                  onChange={(e) => setMessage(e.target.value)}
                  required
                  rows={6}
                  placeholder="Type your message here..."
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
                <p className="text-sm text-gray-500 mt-2">
                  Character count: {message.length}
                </p>
              </div>

              {/* Submit Button */}
              <div className="flex items-center justify-end space-x-3">
                <button
                  type="button"
                  className="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                >
                  Save as Draft
                </button>
                <button
                  type="submit"
                  disabled={loading}
                  className="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                >
                  <FiSend className="w-4 h-4" />
                  <span>{loading ? 'Creating...' : 'Create Broadcast'}</span>
                </button>
              </div>
            </form>
          </div>

          {/* Recent Broadcasts */}
          <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-xl font-semibold text-gray-900">Recent Broadcasts</h2>
              <FiList className="w-5 h-5 text-gray-400" />
            </div>
            <p className="text-gray-500 text-center py-8">No broadcasts yet</p>
          </div>
        </div>
      </div>
    </div>
  )
}

export default BroadcastPage
