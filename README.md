ntisthis
========

some code snippets for accessing and manipulating the training package data available from the http://training.gov.au SOAP feed

With the help of some friends (shoutout to Steven Brain) I put together a few scripts to build http://ntisthis.com. Over the years a few folk have asked how they might do the same and recently Rheinard Korf suggested putting together this repository. It's really a bit of a mess, but hopfully still useful for someone (changing evolutionary pressures make for horrible results, look at how the human knee is put together for a good example)

I'm picking up the training.gov.au info via their SOAP feed using python. If you post a request on the tga contact page http://training.gov.au/Home/Enquiry you should be able to arrange access. The data they provide is not as clean as it could be, and includes a fair bit of formatting information in the XML nodes (tables around the data for example), which is annoyingly fiddly, but you get what you pay for I guess.

Once you've got your user name and password you can connect using the python suds client: https://fedorahosted.org/suds/ (doco: https://fedorahosted.org/suds/wiki/Documentation). I've included some example code here (getdata.py). Go nuts, and build something awesome...

Bonus level: there's some php code here that Julian Davis (juliandavis71) sent to me last year. I'm ashamed to say that I haven't tried it out yet, but it looks promising so I put it up (with Julian's kind permission) in the php directory. Please let me know how it works out
