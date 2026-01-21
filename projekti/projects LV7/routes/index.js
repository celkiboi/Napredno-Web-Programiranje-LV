var express = require('express');
var router = express.Router();
var User = require('../models/user');

/* GET home page. */
router.get('/', function (req, res, next) {
  res.render('index', { title: 'Project Management' });
});

router.get('/login', function (req, res, next) {
  res.render('login');
});

router.post('/login', async function (req, res, next) {
  try {
    const user = await User.findOne({ username: req.body.username });
    if (!user) {
      req.flash('error', 'User not found');
      return res.redirect('/login');
    }
    const isMatch = await user.comparePassword(req.body.password);
    if (!isMatch) {
      req.flash('error', 'Invalid password');
      return res.redirect('/login');
    }
    req.session.user = user;
    res.redirect('/projects');
  } catch (err) {
    req.flash('error', err.message);
    res.redirect('/login');
  }
});

router.get('/register', function (req, res, next) {
  res.render('register');
});

router.post('/register', async function (req, res, next) {
  try {
    var user = new User({
      username: req.body.username,
      email: req.body.email,
      password: req.body.password
    });
    await user.save();
    req.flash('info', 'Registration successful! Please login.');
    res.redirect('/login');
  } catch (err) {
    req.flash('error', err.message);
    res.redirect('/register');
  }
});

router.get('/logout', function (req, res, next) {
  req.session.destroy();
  res.redirect('/');
});

module.exports = router;
