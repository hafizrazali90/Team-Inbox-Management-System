import { useState, useEffect, useRef } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { FiSend, FiPaperclip, FiMoreVertical } from 'react-icons/fi'
import { sendMessage } from '../../store/slices/messagesSlice'
import { addMessageToConversation } from '../../store/slices/conversationsSlice'
import { format } from 'date-fns'
import { toast } from 'react-toastify'

function ChatPanel() {
  const dispatch = useDispatch()
  const { selectedConversation } = useSelector((state) => state.conversations)
  const { sending } = useSelector((state) => state.messages)
  const { user } = useSelector((state) => state.auth)
  const [message, setMessage] = useState('')
  const messagesEndRef = useRef(null)

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' })
  }

  useEffect(() => {
    scrollToBottom()
  }, [selectedConversation?.messages])

  const handleSendMessage = async (e) => {
    e.preventDefault()
    if (!message.trim() || !selectedConversation) return

    const messageData = {
      conversation_id: selectedConversation.id,
      type: 'text',
      content: message.trim(),
    }

    const result = await dispatch(sendMessage(messageData))
    if (result.type === 'messages/sendMessage/fulfilled') {
      setMessage('')
      dispatch(addMessageToConversation(result.payload.data))
      toast.success('Message sent!')
    } else {
      toast.error('Failed to send message')
    }
  }

  if (!selectedConversation) {
    return (
      <div className="flex-1 flex items-center justify-center bg-gray-50">
        <div className="text-center">
          <div className="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
            <FiSend className="w-12 h-12 text-gray-400" />
          </div>
          <h3 className="text-xl font-semibold text-gray-700 mb-2">Select a conversation</h3>
          <p className="text-gray-500">Choose a conversation from the list to start messaging</p>
        </div>
      </div>
    )
  }

  return (
    <div className="flex-1 flex flex-col bg-gray-50">
      {/* Header */}
      <div className="bg-white border-b border-gray-200 px-6 py-4">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="text-lg font-semibold text-gray-900">
              {selectedConversation.contact_name || 'Unknown'}
            </h2>
            <p className="text-sm text-gray-500">{selectedConversation.contact_phone}</p>
          </div>
          <button className="p-2 hover:bg-gray-100 rounded-lg transition">
            <FiMoreVertical className="w-5 h-5 text-gray-600" />
          </button>
        </div>
      </div>

      {/* Messages */}
      <div className="flex-1 overflow-y-auto p-6 space-y-4">
        {selectedConversation.messages?.map((msg) => {
          const isOutbound = msg.direction === 'outbound'
          const sender = msg.sender

          return (
            <div
              key={msg.id}
              className={`flex ${isOutbound ? 'justify-end' : 'justify-start'}`}
            >
              <div className={`max-w-md ${isOutbound ? 'order-2' : 'order-1'}`}>
                <div
                  className={`px-4 py-3 rounded-2xl ${
                    isOutbound
                      ? 'bg-primary-600 text-white'
                      : 'bg-white text-gray-900 border border-gray-200'
                  }`}
                >
                  {msg.content && <p className="text-sm">{msg.content}</p>}
                  {msg.media_url && (
                    <div className="mt-2">
                      <img src={msg.media_url} alt="media" className="rounded-lg max-w-xs" />
                    </div>
                  )}
                </div>
                <div className={`flex items-center mt-1 space-x-2 ${isOutbound ? 'justify-end' : 'justify-start'}`}>
                  {isOutbound && sender && (
                    <span className="text-xs text-gray-500">{sender.name}</span>
                  )}
                  <span className="text-xs text-gray-400">
                    {format(new Date(msg.created_at), 'HH:mm')}
                  </span>
                </div>
              </div>
            </div>
          )
        })}
        <div ref={messagesEndRef} />
      </div>

      {/* Input */}
      <div className="bg-white border-t border-gray-200 px-6 py-4">
        <form onSubmit={handleSendMessage} className="flex items-center space-x-3">
          <button
            type="button"
            className="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition"
          >
            <FiPaperclip className="w-5 h-5" />
          </button>
          <input
            type="text"
            value={message}
            onChange={(e) => setMessage(e.target.value)}
            placeholder="Type a message..."
            className="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
          <button
            type="submit"
            disabled={sending || !message.trim()}
            className="p-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <FiSend className="w-5 h-5" />
          </button>
        </form>
      </div>
    </div>
  )
}

export default ChatPanel
