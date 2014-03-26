



var apvhdrs = new Apvhdrs();
apvhdrs.url = '../www/api/r/apvdue?due=2014-02-26';
apvhdrs.fetch({reset: true});


var reportApvhdr = new ReportApvhdr();
console.log(reportApvhdr);
reportApvhdr.render();
