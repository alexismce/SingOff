const express = require('express');
const app = express();
const port = process.env.PORT || 3000;

app.use(express.static('public')); // Serve static files from the 'public' directory

app.get('/', (req, res) => {
  res.send('Welcome to the Sign-Off Project!');
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
