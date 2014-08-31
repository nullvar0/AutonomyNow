var markDown = {

bold: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "**", "**");},
italic: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "*", "*");},
strikethrough: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "~~", "~~");},
header: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "#", "");},
link: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[", "](http://example.com)", "http://example.com", "link text");},
image: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "![Alt Text](", ")", "", "http://example.com/image.jpg");},
fixed: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "```", "```");},

};