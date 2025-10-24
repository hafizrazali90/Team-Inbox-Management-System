import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import api from '../../services/api'

export const fetchTags = createAsyncThunk('tags/fetchTags', async (_, { rejectWithValue }) => {
  try {
    const response = await api.get('/tags')
    return response.data
  } catch (error) {
    return rejectWithValue(error.response?.data?.message || 'Failed to fetch tags')
  }
})

export const addTagToConversation = createAsyncThunk(
  'tags/addToConversation',
  async ({ conversationId, tagId }, { rejectWithValue }) => {
    try {
      const response = await api.post(`/conversations/${conversationId}/tags`, { tag_id: tagId })
      return response.data
    } catch (error) {
      return rejectWithValue(error.response?.data?.message || 'Failed to add tag')
    }
  }
)

const tagsSlice = createSlice({
  name: 'tags',
  initialState: {
    list: [],
    loading: false,
    error: null,
  },
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchTags.pending, (state) => {
        state.loading = true
      })
      .addCase(fetchTags.fulfilled, (state, action) => {
        state.loading = false
        state.list = action.payload.tags
      })
      .addCase(fetchTags.rejected, (state, action) => {
        state.loading = false
        state.error = action.payload
      })
  },
})

export default tagsSlice.reducer
