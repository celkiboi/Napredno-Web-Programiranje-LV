var express = require('express');
var router = express.Router();
var Project = require('../models/project');
var User = require('../models/user');

// Middleware to check if user is logged in
function requireLogin(req, res, next) {
    if (!req.session.user) {
        req.flash('error', 'You must be logged in');
        return res.redirect('/login');
    }
    next();
}

router.use(requireLogin);

router.get('/', async function (req, res, next) {
    try {
        const ledProjects = await Project.find({ manager: req.session.user._id, archived: false });
        const memberProjects = await Project.find({ members: req.session.user._id, archived: false });
        res.render('projects/index', { ledProjects: ledProjects, memberProjects: memberProjects });
    } catch (err) {
        next(err);
    }
});

router.get('/archive', async function (req, res, next) {
    try {
        const projects = await Project.find({
            $or: [{ manager: req.session.user._id }, { members: req.session.user._id }],
            archived: true
        }).populate('manager');
        res.render('projects/archive', { projects: projects });
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
            manager: req.session.user._id,
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
        const project = await Project.findById(req.params.id).populate('manager').populate('members');
        const isManager = project.manager && project.manager._id.toString() === req.session.user._id.toString();

        let users = [];
        if (isManager) {
            const currentMemberIds = project.members.map(m => m._id.toString());
            currentMemberIds.push(project.manager._id.toString());

            users = await User.find({
                _id: { $nin: currentMemberIds }
            });
        }

        res.render('projects/show', { project: project, isManager: isManager, users: users });
    } catch (err) {
        next(err);
    }
});

router.get('/:id/edit', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        const isManager = project.manager && project.manager.toString() === req.session.user._id.toString();
        // If not manager and not member, deny? Or purely view logic? User story says members "can only change attributes performed_jobs". 
        // We will pass isManager to view to disable fields.
        res.render('projects/edit', { project: project, isManager: isManager });
    } catch (err) {
        next(err);
    }
});

router.post('/:id', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        const isManager = project.manager && project.manager.toString() === req.session.user._id.toString();

        if (isManager) {
            project.name = req.body.name;
            project.description = req.body.description;
            project.price = req.body.price;
            project.start_date = req.body.start_date;
            project.end_date = req.body.end_date;
        }

        // Members (and Manager) can edit jobs_done. 
        // Actually specs say "On projects where they are members they can ONLY change attribute performed_jobs".
        // Manager presumably can change everything.
        project.jobs_done = req.body.jobs_done;

        await project.save();
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

router.post('/:id/delete', async function (req, res, next) {
    try {
        // Only manager can delete/archive? Spec doesn't say explicit forbid for delete, but implies manager authority.
        // Assuming delete is manager only.
        const project = await Project.findById(req.params.id);
        if (project.manager.toString() !== req.session.user._id.toString()) {
            return res.status(403).send('Unauthorized');
        }
        await Project.findByIdAndDelete(req.params.id);
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

router.post('/:id/archive', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (project.manager.toString() !== req.session.user._id.toString()) {
            return res.status(403).send('Unauthorized');
        }
        project.archived = true;
        await project.save();
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

router.post('/:id/members', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        // Ensure only manager can add members
        if (project.manager.toString() !== req.session.user._id.toString()) {
            req.flash('error', 'Only manager can add members');
            return res.redirect('/projects/' + project._id);
        }

        const user = await User.findOne({ username: req.body.member });
        if (!user) {
            req.flash('error', 'User not found');
            return res.redirect('/projects/' + project._id);
        }

        // Check if already member
        if (project.members.includes(user._id) || project.manager.toString() === user._id.toString()) {
            req.flash('error', 'User already in project');
            return res.redirect('/projects/' + project._id);
        }

        project.members.push(user._id);
        await project.save();
        res.redirect('/projects/' + req.params.id);
    } catch (err) {
        next(err);
    }
});

module.exports = router;
