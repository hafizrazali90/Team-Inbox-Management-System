import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import api from '../../services/api'

export const sendMessage = createAsyncThunk(
  'messages/sendMessage',
  async (messageData, { rejectWithValue }) => {
    try {
      const response = await api.post('/messages/send', messageData)
      return response.data
    } catch (error) {
      return rejectWithValue(error.response?.data?.message || 'Failed to send message')
    }
  }
)

const messagesSlice = createSlice({
  name: 'messages',
  initialState: {
    sending: false,
    error: null,
  },
  reducers: {
    clearError: (state) => {
      state.error = null
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(sendMessage.pending, (state) => {
        state.sending = true
        state.error = null
      })
      .addCase(sendMessage.fulfilled, (state) => {
        state.sending = false
      })
      .addCase(sendMessage.rejected, (state, action) => {
        state.sending = false
        state.error = action.payload
      })
  },
})

export const { clearError } = messagesSlice.actions
export default messagesSlice.reducer
