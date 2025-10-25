# 🍎 TIMS Setup Guide for Mac - Complete Beginner

**Total Time: ~15-20 minutes** (mostly waiting for downloads)

This guide assumes you have **ZERO coding experience**. Just copy and paste the commands exactly as shown!

---

## 📋 What We'll Install

1. **Homebrew** - Package manager (makes installing things easy)
2. **PHP** - Backend programming language
3. **Composer** - PHP package manager
4. **Node.js** - JavaScript runtime (for frontend)
5. **Git** - Version control (to download the code)

---

## 🚀 Step-by-Step Instructions

### **Step 1: Open Terminal**

1. Press `Command (⌘) + Space` to open Spotlight
2. Type: `Terminal`
3. Press `Enter`

A window with black/white background will open. This is your Terminal.

---

### **Step 2: Install Homebrew** (Mac's Package Manager)

**Copy this entire command and paste it into Terminal:**

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

**Press Enter and wait...**

- It will ask for your Mac password (the one you use to login)
- Type your password (you won't see it typing - that's normal!)
- Press Enter
- **Wait 5-10 minutes** for it to install

When it's done, you'll see a message about "Next steps". If it shows you some commands to run (they start with `echo` and `eval`), **copy and run those commands too**.

**Verify Homebrew installed:**
```bash
brew --version
```

You should see: `Homebrew 4.x.x` or similar.

✅ **Checkpoint 1 Complete!**

---

### **Step 3: Install PHP**

**Copy and paste this command:**

```bash
brew install php@8.2
```

Press Enter and wait (~3-5 minutes).

**Verify PHP installed:**
```bash
php --version
```

You should see: `PHP 8.2.x` or similar.

✅ **Checkpoint 2 Complete!**

---

### **Step 4: Install Composer**

**Copy and paste this command:**

```bash
brew install composer
```

Press Enter and wait (~2 minutes).

**Verify Composer installed:**
```bash
composer --version
```

You should see: `Composer version 2.x.x`

✅ **Checkpoint 3 Complete!**

---

### **Step 5: Install Node.js**

**Copy and paste this command:**

```bash
brew install node
```

Press Enter and wait (~3-5 minutes).

**Verify Node.js installed:**
```bash
node --version
npm --version
```

You should see version numbers for both.

✅ **Checkpoint 4 Complete!**

---

### **Step 6: Install Git** (Probably Already Installed)

**Check if Git is installed:**
```bash
git --version
```

If you see a version number, **skip to Step 7**.

If not, install it:
```bash
brew install git
```

✅ **Checkpoint 5 Complete!**

---

### **Step 7: Download the Project**

**Choose where to put the project:**

I recommend your Desktop for easy access.

**Copy and paste these commands ONE BY ONE:**

```bash
cd ~/Desktop
```

```bash
git clone https://github.com/hafizrazali90/Team-Inbox-Management-System.git
```

```bash
cd Team-Inbox-Management-System
```

```bash
git checkout claude/tims-project-scaffold-011CUSUPj7MaNBijVBcUR9Yg
```

**You should now have a folder called `Team-Inbox-Management-System` on your Desktop!**

✅ **Checkpoint 6 Complete!**

---

### **Step 8: Run the Automatic Setup Script**

This script does everything automatically:
- Sets up the backend
- Creates the database
- Installs frontend dependencies
- Creates test users

**Copy and paste this command:**

```bash
chmod +x quick-setup.sh && ./quick-setup.sh
```

Press Enter and wait (~2-5 minutes).

You'll see lots of text scrolling by. **This is normal!**

When it's done, you should see:
```
✅ Setup complete!
```

✅ **Checkpoint 7 Complete!**

---

### **Step 9: Start the Backend Server**

**Open a NEW Terminal window** (don't close the first one):
- Press `Command (⌘) + N` while in Terminal

In this NEW window, run:

```bash
cd ~/Desktop/Team-Inbox-Management-System
./start-backend.sh
```

You should see:
```
Laravel development server started: http://127.0.0.1:8000
```

**✨ Leave this window OPEN and running!**

✅ **Backend is Running!**

---

### **Step 10: Start the Frontend Server**

**Open ANOTHER new Terminal window** (now you'll have 2 running):
- Press `Command (⌘) + N` again

In this THIRD window, run:

```bash
cd ~/Desktop/Team-Inbox-Management-System
./start-frontend.sh
```

You should see:
```
VITE ready in XXX ms
Local: http://localhost:5173
```

**✨ Leave this window OPEN and running too!**

✅ **Frontend is Running!**

---

### **Step 11: Open the Application**

**Open your web browser (Safari, Chrome, etc.) and go to:**

```
http://localhost:5173
```

You should see the **TIMS Login Page**! 🎉

---

### **Step 12: Login**

Use these test credentials:

**Admin Account:**
- Email: `admin@tims.local`
- Password: `password123`

**Click "Login"** and you should see the dashboard!

✅ **SUCCESS! You're running the application!** 🎉🎉🎉

---

## 🖥️ Quick Reference

**To start the application later:**

1. Open Terminal
2. Start Backend:
   ```bash
   cd ~/Desktop/Team-Inbox-Management-System
   ./start-backend.sh
   ```
3. Open a NEW Terminal window
4. Start Frontend:
   ```bash
   cd ~/Desktop/Team-Inbox-Management-System
   ./start-frontend.sh
   ```
5. Open browser: `http://localhost:5173`

**To stop the servers:**
- Press `Control + C` in each Terminal window

---

## 🆘 Troubleshooting

### Problem: "command not found: brew"
**Solution:** Homebrew didn't install correctly. Try Step 2 again.

### Problem: "Port 8000 already in use"
**Solution:** Something else is using that port. Run:
```bash
lsof -ti:8000 | xargs kill
```
Then try starting the backend again.

### Problem: "Port 5173 already in use"
**Solution:** Run:
```bash
lsof -ti:5173 | xargs kill
```
Then try starting the frontend again.

### Problem: Login says "Network Error"
**Solution:** Make sure BOTH servers are running (you should have 2 Terminal windows open).

### Problem: "Permission denied"
**Solution:** Run:
```bash
chmod +x *.sh
```

---

## 📝 Summary - What Did We Do?

1. ✅ Installed development tools (PHP, Node.js, Composer, Git)
2. ✅ Downloaded the TIMS project from GitHub
3. ✅ Set up the database and created test users
4. ✅ Started both servers (backend API + frontend UI)
5. ✅ Logged into the application

**You now have a fully working Team Inbox Management System running on your Mac!** 🎉

---

## 💡 Next Steps

- Explore the dashboard
- Try sending messages
- Check out different user roles (Admin, Manager, Agent)
- Read the main README.md for more features

---

**Need help? Just ask! I'm here to guide you through any issues.** 😊
