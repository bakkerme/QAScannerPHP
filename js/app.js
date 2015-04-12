require('./models/Record.js')
require('./collections/RecordList.js')
require('./views/RecordView.js')
require('./views/RecordListView.js')

var records = new RecordListView();
records.render();