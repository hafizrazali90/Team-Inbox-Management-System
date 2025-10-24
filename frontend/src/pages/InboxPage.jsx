import { useState, useEffect } from 'react'
import { useDispatch } from 'react-redux'
import Sidebar from '../components/Sidebar/Sidebar'
import ConversationList from '../components/Conversations/ConversationList'
import ChatPanel from '../components/Chat/ChatPanel'
import ProfilePanel from '../components/Profile/ProfilePanel'
import { fetchConversationById } from '../store/slices/conversationsSlice'

function InboxPage() {
  const dispatch = useDispatch()
  const [showProfile, setShowProfile] = useState(true)

  const handleSelectConversation = (conversation) => {
    dispatch(fetchConversationById(conversation.id))
  }

  return (
    <div className="flex h-screen bg-gray-50">
      <Sidebar />
      <ConversationList onSelectConversation={handleSelectConversation} />
      <ChatPanel />
      {showProfile && <ProfilePanel onClose={() => setShowProfile(false)} />}
    </div>
  )
}

export default InboxPage
