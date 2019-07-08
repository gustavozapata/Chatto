const mongoose = require("mongoose");
const mySchema = mongoose.Schema(
  {
    _id: mongoose.Schema.Types.ObjectId,
    user: String,
    message: String
  },
  {
    versionKey: false
  }
);

module.exports = mongoose.model("Chat", mySchema);
