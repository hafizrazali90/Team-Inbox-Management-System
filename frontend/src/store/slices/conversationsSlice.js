import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import api from '../../services/api'

export const fetchConversations = createAsyncThunk(
  'conversations/fetchConversations',
  async (filters = {}, { rejectWithValue }) => {
    try {
      const response = await api.get('/conversations', { params: filters })
      return response.data
    } catch (error) {
      return rejectWithValue(error.response?.data?.message || 'Failed to fetch conversations')
    }
  }
)

export const fetchConversationById = createAsyncThunk(
  'conversations/fetchConversationById',
  async (id, { rejectWithValue }) => {
    try {
      const response = await api.get(`/conversations/${id}`)
      return response.data
    } catch (error) {
      return rejectWithValue(error.response?.data?.message || 'Failed to fetch conversation')
    }
  }
)

export const assignConversation = createAsyncThunk(
  'conversations/assignConversation',
  async ({ id, userId }, { rejectWithValue }) => {
    try {
      const response = await api.post(`/conversations/${id}/assign`, { user_id: userId })
      return response.data
    } catch (error) {
      return rejectWithValue(error.response?.data?.message || 'Failed to assign conversation')
    }
  }
)

export const updateConversationStatus = createAsyncThunk(
  'conversations/updateStatus',
  async ({ id, status }, { rejectWithValue }) => {
    try {
      const response = await api.patch(`/conversations/${id}/status`, { status })
      return response.data
    } catch (error) {
      return rejectWithValue(error.response?.data?.message || 'Failed to update status')
    }
  }
)

const conversationsSlice = createSlice({
  name: 'conversations',
  initialState: {
    list: [],
    selectedConversation: null,
    loading: false,
    error: null,
    pagination: null,
  },
  reducers: {
    selectConversation: (state, action) => {
      state.selectedConversation = action.payload
    },
    clearSelectedConversation: (state) => {
      state.selectedConversation = null
    },
    addMessageToConversation: (state, action) => {
      if (state.selectedConversation?.id === action.payload.conversation_id) {
        state.selectedConversation.messages.push(action.payload)
      }
    },
  },
  extraReducers: (builder) => {
    builder
      // Fetch conversations
      .addCase(fetchConversations.pending, (state) => {
        state.loading = true
        state.error = null
      })
      .addCase(fetchConversations.fulfilled, (state, action) => {
        state.loading = false
        state.list = action.payload.conversations.data
        state.pagination = {
          current_page: action.payload.conversations.current_page,
          last_page: action.payload.conversations.last_page,
          total: action.payload.conversations.total,
        }
      })
      .addCase(fetchConversations.rejected, (state, action) => {
        state.loading = false
        state.error = action.payload
      })
      // Fetch conversation by ID
      .addCase(fetchConversationById.fulfilled, (state, action) => {
        state.selectedConversation = action.payload.conversation
      })
      // Update status
      .addCase(updateConversationStatus.fulfilled, (state, action) => {
        const index = state.list.findIndex(c => c.id === action.payload.conversation.id)
        if (index !== -1) {
          state.list[index] = action.payload.conversation
        }
        if (state.selectedConversation?.id === action.payload.conversation.id) {
          state.selectedConversation.status = action.payload.conversation.status
        }
      })
  },
})

export const { selectConversation, clearSelectedConversation, addMessageToConversation } = conversationsSlice.actions
export default conversationsSlice.reducer
