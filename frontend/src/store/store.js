import { configureStore } from '@reduxjs/toolkit'
import authReducer from './slices/authSlice'
import conversationsReducer from './slices/conversationsSlice'
import messagesReducer from './slices/messagesSlice'
import tagsReducer from './slices/tagsSlice'

export const store = configureStore({
  reducer: {
    auth: authReducer,
    conversations: conversationsReducer,
    messages: messagesReducer,
    tags: tagsReducer,
  },
})
