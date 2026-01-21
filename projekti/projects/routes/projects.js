var express = require('express');
var router = express.Router();
var Project = require('../models/project');

router.get('/', async function (req, res, next) {
    try {
        const projects = await Project.find();
        res.render('projects/index', { projects: projects });
    } catch (err) {
        next(err);
    }
});

router.get('/new', function (req, res, next) {
    res.render('projects/new');
});

router.post('/', async function (req, res, next) {
    try {
        var project = new Project({
            name: req.body.name,
            description: req.body.description,
            price: req.body.price,
            jobs_done: req.body.jobs_done,
            start_date: req.body.start_date,
            end_date: req.body.end_date,
            members: []
        });
        await project.save();
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

router.get('/:id', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        res.render('projects/show', { project: project });
    } catch (err) {
        next(err);
    }
});

router.get('/:id/edit', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        res.render('projects/edit', { project: project });
    } catch (err) {
        next(err);
    }
});

router.post('/:id', async function (req, res, next) {
    try {
        await Project.findByIdAndUpdate(req.params.id, {
            name: req.body.name,
            description: req.body.description,
            price: req.body.price,
            jobs_done: req.body.jobs_done,
            start_date: req.body.start_date,
            end_date: req.body.end_date
        });
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

router.post('/:id/delete', async function (req, res, next) {
    try {
        await Project.findByIdAndDelete(req.params.id);
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

router.post('/:id/members', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        project.members.push(req.body.member);
        await project.save();
        res.redirect('/projects/' + req.params.id);
    } catch (err) {
        next(err);
    }
});

module.exports = router;
