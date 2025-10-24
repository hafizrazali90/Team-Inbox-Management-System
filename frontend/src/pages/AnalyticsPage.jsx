import { useEffect, useState } from 'react'
import { FiMessageSquare, FiClock, FiUsers, FiTrendingUp } from 'react-icons/fi'
import Sidebar from '../components/Sidebar/Sidebar'
import api from '../services/api'

function AnalyticsPage() {
  const [analytics, setAnalytics] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchAnalytics()
  }, [])

  const fetchAnalytics = async () => {
    try {
      const response = await api.get('/analytics/summary')
      setAnalytics(response.data)
    } catch (error) {
      console.error('Failed to fetch analytics', error)
    } finally {
      setLoading(false)
    }
  }

  const StatCard = ({ icon: Icon, title, value, color, description }) => (
    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div className="flex items-center justify-between mb-4">
        <div className={`w-12 h-12 ${color} rounded-lg flex items-center justify-center`}>
          <Icon className="w-6 h-6 text-white" />
        </div>
      </div>
      <h3 className="text-2xl font-bold text-gray-900 mb-1">{value}</h3>
      <p className="text-sm font-medium text-gray-600">{title}</p>
      {description && <p className="text-xs text-gray-500 mt-2">{description}</p>}
    </div>
  )

  if (loading) {
    return (
      <div className="flex h-screen">
        <Sidebar />
        <div className="flex-1 flex items-center justify-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        </div>
      </div>
    )
  }

  return (
    <div className="flex h-screen bg-gray-50">
      <Sidebar />

      <div className="flex-1 overflow-y-auto">
        <div className="max-w-7xl mx-auto p-8">
          {/* Header */}
          <div className="mb-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-2">Analytics Dashboard</h1>
            <p className="text-gray-600">Monitor team performance and conversation metrics</p>
          </div>

          {/* Stats Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <StatCard
              icon={FiMessageSquare}
              title="Total Chats Today"
              value={analytics?.summary?.total_chats_today || 0}
              color="bg-primary-600"
            />
            <StatCard
              icon={FiTrendingUp}
              title="Open Conversations"
              value={analytics?.summary?.open_chats || 0}
              color="bg-green-600"
              description={`${analytics?.summary?.closed_chats || 0} closed`}
            />
            <StatCard
              icon={FiClock}
              title="Avg Response Time"
              value={`${analytics?.summary?.avg_response_time_minutes?.toFixed(1) || 0} min`}
              color="bg-amber-600"
            />
            <StatCard
              icon={FiUsers}
              title="Pending Follow-ups"
              value={analytics?.summary?.follow_up_count || 0}
              color="bg-purple-600"
            />
          </div>

          {/* Agent Performance */}
          <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 className="text-xl font-semibold text-gray-900 mb-6">Top Agents (By Chats)</h2>
            <div className="space-y-4">
              {analytics?.chats_per_agent?.length > 0 ? (
                analytics.chats_per_agent.map((agent, index) => (
                  <div key={agent.user_id} className="flex items-center justify-between">
                    <div className="flex items-center space-x-3">
                      <div className="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <span className="text-sm font-semibold text-primary-600">
                          #{index + 1}
                        </span>
                      </div>
                      <div>
                        <p className="font-medium text-gray-900">{agent.user_name}</p>
                        <p className="text-sm text-gray-500">{agent.chat_count} conversations</p>
                      </div>
                    </div>
                    <div className="w-32 bg-gray-200 rounded-full h-2">
                      <div
                        className="bg-primary-600 h-2 rounded-full"
                        style={{
                          width: `${(agent.chat_count / analytics.chats_per_agent[0].chat_count) * 100}%`
                        }}
                      />
                    </div>
                  </div>
                ))
              ) : (
                <p className="text-gray-500 text-center py-4">No data available</p>
              )}
            </div>
          </div>

          {/* AI Performance */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-6">AI Performance</h2>
              <div className="text-center">
                <div className="inline-flex items-center justify-center w-32 h-32 bg-teal-100 rounded-full mb-4">
                  <span className="text-4xl font-bold text-teal-600">
                    {analytics?.summary?.ai_handoff_rate?.toFixed(0) || 0}%
                  </span>
                </div>
                <p className="text-gray-600">AI Handoff Rate</p>
                <p className="text-sm text-gray-500 mt-2">
                  Sofia AI is handling a portion of conversations automatically
                </p>
              </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-6">Status Distribution</h2>
              <div className="space-y-3">
                <div className="flex items-center justify-between">
                  <span className="text-gray-600">Open</span>
                  <span className="font-semibold text-green-600">
                    {analytics?.summary?.open_chats || 0}
                  </span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-gray-600">Pending</span>
                  <span className="font-semibold text-yellow-600">
                    {analytics?.summary?.pending_chats || 0}
                  </span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-gray-600">Closed</span>
                  <span className="font-semibold text-gray-600">
                    {analytics?.summary?.closed_chats || 0}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default AnalyticsPage
