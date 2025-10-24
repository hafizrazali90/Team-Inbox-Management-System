import { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { FiSearch, FiFilter } from 'react-icons/fi'
import { fetchConversations, selectConversation } from '../../store/slices/conversationsSlice'
import { format } from 'date-fns'

function ConversationList({ onSelectConversation }) {
  const dispatch = useDispatch()
  const { list, loading, selectedConversation } = useSelector((state) => state.conversations)
  const [search, setSearch] = useState('')
  const [statusFilter, setStatusFilter] = useState('all')

  useEffect(() => {
    const filters = {}
    if (statusFilter !== 'all') filters.status = statusFilter
    if (search) filters.search = search

    dispatch(fetchConversations(filters))
  }, [dispatch, statusFilter, search])

  const handleSelectConversation = (conversation) => {
    dispatch(selectConversation(conversation))
    if (onSelectConversation) onSelectConversation(conversation)
  }

  const getStatusColor = (status) => {
    switch (status) {
      case 'open': return 'bg-green-100 text-green-700'
      case 'pending': return 'bg-yellow-100 text-yellow-700'
      case 'closed': return 'bg-gray-100 text-gray-700'
      default: return 'bg-gray-100 text-gray-700'
    }
  }

  return (
    <div className="w-96 bg-white border-r border-gray-200 flex flex-col h-screen">
      {/* Header */}
      <div className="p-4 border-b border-gray-200">
        <h2 className="text-xl font-bold text-gray-900 mb-4">Conversations</h2>

        {/* Search */}
        <div className="relative mb-3">
          <FiSearch className="absolute left-3 top-3 text-gray-400" />
          <input
            type="text"
            placeholder="Search conversations..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
        </div>

        {/* Status Filter */}
        <div className="flex space-x-2">
          {['all', 'open', 'pending', 'closed'].map((status) => (
            <button
              key={status}
              onClick={() => setStatusFilter(status)}
              className={`px-3 py-1 text-sm rounded-lg transition ${
                statusFilter === status
                  ? 'bg-primary-600 text-white'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              }`}
            >
              {status.charAt(0).toUpperCase() + status.slice(1)}
            </button>
          ))}
        </div>
      </div>

      {/* Conversation List */}
      <div className="flex-1 overflow-y-auto">
        {loading ? (
          <div className="flex items-center justify-center h-32">
            <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          </div>
        ) : list.length === 0 ? (
          <div className="text-center py-8 text-gray-500">
            No conversations found
          </div>
        ) : (
          <div className="divide-y divide-gray-100">
            {list.map((conversation) => (
              <div
                key={conversation.id}
                onClick={() => handleSelectConversation(conversation)}
                className={`p-4 cursor-pointer transition ${
                  selectedConversation?.id === conversation.id
                    ? 'bg-primary-50 border-l-4 border-primary-600'
                    : 'hover:bg-gray-50'
                }`}
              >
                <div className="flex items-start justify-between mb-2">
                  <div className="flex-1 min-w-0">
                    <h3 className="font-semibold text-gray-900 truncate">
                      {conversation.contact_name || 'Unknown'}
                    </h3>
                    <p className="text-xs text-gray-500">{conversation.contact_phone}</p>
                  </div>
                  <span className={`text-xs px-2 py-1 rounded-full ${getStatusColor(conversation.status)}`}>
                    {conversation.status}
                  </span>
                </div>

                {conversation.last_message && (
                  <p className="text-sm text-gray-600 truncate mb-2">
                    {conversation.last_message.content || '[Media]'}
                  </p>
                )}

                <div className="flex items-center justify-between">
                  <div className="flex space-x-1">
                    {conversation.tags?.slice(0, 2).map((tag) => (
                      <span
                        key={tag.id}
                        className="text-xs px-2 py-0.5 rounded"
                        style={{ backgroundColor: tag.color + '20', color: tag.color }}
                      >
                        {tag.name}
                      </span>
                    ))}
                  </div>
                  {conversation.last_message_at && (
                    <span className="text-xs text-gray-400">
                      {format(new Date(conversation.last_message_at), 'HH:mm')}
                    </span>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}

export default ConversationList
