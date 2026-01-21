var mongoose = require('mongoose');
var Schema = mongoose.Schema;
var projectSchema = new Schema({
    name: String,
    description: String,
    price: Number,
    jobs_done: String,
    start_date: Date,
    end_date: Date,
    manager: { type: Schema.Types.ObjectId, ref: 'User' },
    members: [{ type: Schema.Types.ObjectId, ref: 'User' }],
    archived: { type: Boolean, default: false }
});
module.exports = mongoose.model('Project', projectSchema);
