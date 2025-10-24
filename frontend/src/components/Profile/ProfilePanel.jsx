import { useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { FiUser, FiPhone, FiTag, FiClock, FiX } from 'react-icons/fi'
import { fetchTags } from '../../store/slices/tagsSlice'
import { format } from 'date-fns'

function ProfilePanel({ onClose }) {
  const dispatch = useDispatch()
  const { selectedConversation } = useSelector((state) => state.conversations)
  const { list: tags } = useSelector((state) => state.tags)

  useEffect(() => {
    dispatch(fetchTags())
  }, [dispatch])

  if (!selectedConversation) return null

  return (
    <div className="w-80 bg-white border-l border-gray-200 flex flex-col h-screen overflow-y-auto">
      {/* Header */}
      <div className="p-6 border-b border-gray-200">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg font-semibold text-gray-900">Contact Info</h3>
          {onClose && (
            <button onClick={onClose} className="p-1 hover:bg-gray-100 rounded">
              <FiX className="w-5 h-5 text-gray-600" />
            </button>
          )}
        </div>
        <div className="flex flex-col items-center">
          <div className="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mb-3">
            <FiUser className="w-10 h-10 text-primary-600" />
          </div>
          <h4 className="font-semibold text-gray-900">
            {selectedConversation.contact_name || 'Unknown'}
          </h4>
          <p className="text-sm text-gray-500">{selectedConversation.contact_phone}</p>
        </div>
      </div>

      {/* Details */}
      <div className="p-6 space-y-6">
        {/* Status */}
        <div>
          <label className="text-xs font-semibold text-gray-500 uppercase mb-2 block">
            Status
          </label>
          <select
            value={selectedConversation.status}
            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
          >
            <option value="open">Open</option>
            <option value="pending">Pending</option>
            <option value="closed">Closed</option>
          </select>
        </div>

        {/* Assigned Agent */}
        <div>
          <label className="text-xs font-semibold text-gray-500 uppercase mb-2 block">
            Assigned To
          </label>
          <div className="flex items-center space-x-2 text-sm">
            <FiUser className="w-4 h-4 text-gray-400" />
            <span className="text-gray-900">
              {selectedConversation.assigned_user?.name || 'Unassigned'}
            </span>
          </div>
        </div>

        {/* Tags */}
        <div>
          <label className="text-xs font-semibold text-gray-500 uppercase mb-2 block">
            Tags
          </label>
          <div className="flex flex-wrap gap-2">
            {selectedConversation.tags?.map((tag) => (
              <span
                key={tag.id}
                className="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                style={{ backgroundColor: tag.color + '20', color: tag.color }}
              >
                <FiTag className="w-3 h-3 mr-1" />
                {tag.name}
              </span>
            ))}
          </div>
          <button className="mt-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
            + Add Tag
          </button>
        </div>

        {/* Follow-up */}
        {selectedConversation.follow_up_at && (
          <div>
            <label className="text-xs font-semibold text-gray-500 uppercase mb-2 block">
              Follow-up
            </label>
            <div className="flex items-center space-x-2 text-sm">
              <FiClock className="w-4 h-4 text-amber-500" />
              <span className="text-gray-900">
                {format(new Date(selectedConversation.follow_up_at), 'MMM dd, yyyy HH:mm')}
              </span>
            </div>
          </div>
        )}

        {/* Metrics */}
        <div>
          <label className="text-xs font-semibold text-gray-500 uppercase mb-2 block">
            Metrics
          </label>
          <div className="space-y-2 text-sm">
            <div className="flex justify-between">
              <span className="text-gray-600">Messages:</span>
              <span className="font-medium">{selectedConversation.response_count || 0}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Created:</span>
              <span className="font-medium">
                {format(new Date(selectedConversation.created_at), 'MMM dd, yyyy')}
              </span>
            </div>
          </div>
        </div>

        {/* Notes */}
        <div>
          <label className="text-xs font-semibold text-gray-500 uppercase mb-2 block">
            Internal Notes
          </label>
          <div className="space-y-2 max-h-40 overflow-y-auto">
            {selectedConversation.notes?.map((note) => (
              <div key={note.id} className="p-3 bg-yellow-50 rounded-lg">
                <p className="text-sm text-gray-900">{note.content}</p>
                <div className="flex items-center justify-between mt-2">
                  <span className="text-xs text-gray-500">{note.user?.name}</span>
                  <span className="text-xs text-gray-400">
                    {format(new Date(note.created_at), 'MMM dd')}
                  </span>
                </div>
              </div>
            ))}
          </div>
          <button className="mt-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
            + Add Note
          </button>
        </div>
      </div>
    </div>
  )
}

export default ProfilePanel
