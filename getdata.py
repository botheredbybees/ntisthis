import suds
from suds.client import Client
from suds.wsse import *
import urllib
import re
import unicodedata
import shutil

all_chars = (unichr(i) for i in xrange(0x110000))
control_chars = ''.join(map(unichr, range(0,32) + range(127,160)))

control_char_re = re.compile('[%s]' % re.escape(control_chars))

def remove_control_chars(s):
	return control_char_re.sub('', s)

url ='https://ws.sandbox.training.gov.au/Deewr.Tga.Webservices/TrainingComponentService.svc?wsdl'
orgurl='https://ws.sandbox.training.gov.au/Deewr.Tga.Webservices/OrganisationService.svc?wsdl'

####################################################
username='INSERT YOUR USERNAME HERE'
password='AND YOUR PASSWORD HERE'
####################################################

xmlbaseurl='http://training.gov.au/TrainingComponentFiles/'
xmlfolder="itemxml"

client = suds.client.Client(url)
#print client

security = Security()
token = UsernameToken(username, password)
security.tokens.append(token)
client.set_options(wsse=security)

unitdict={}

def get_details(codetoget):
	"""ask the webservice for information on this piece of curriculum"""
	TrainingComponentDetailsRequest= client.factory.create('TrainingComponentDetailsRequest')
	TrainingComponentDetailsRequest.Code=codetoget
	TrainingComponentInformationRequested=client.factory.create('TrainingComponentInformationRequested')
	TrainingComponentInformationRequested.ShowReleases=True
	TrainingComponentInformationRequested.ShowUnitGrid=True
	TrainingComponentInformationRequested.ShowComponents=True
	TrainingComponentDetailsRequest.InformationRequest=TrainingComponentInformationRequested
	return client.service.GetDetails(TrainingComponentDetailsRequest)

def get_xml(codetoget):
	"""Download the xml for a given training component and return the filename """
	xmlfilename = ''
	try:
		TrainingComponentDetailsRequest= client.factory.create('TrainingComponentDetailsRequest')
		TrainingComponentDetailsRequest.Code=codetoget
		TrainingComponentInformationRequested=client.factory.create('TrainingComponentInformationRequested')
		TrainingComponentInformationRequested.ShowReleases=True
		TrainingComponentInformationRequested.ShowUnitGrid=True
		TrainingComponentInformationRequested.ShowFiles=True
		TrainingComponentInformationRequested.ShowComponents=True
		TrainingComponentDetailsRequest.InformationRequest=TrainingComponentInformationRequested
		try:
			result= client.service.GetDetails(TrainingComponentDetailsRequest)
			#print result
			for unit in result.Releases[0]:
				try:
					for fyle in unit.Files.ReleaseFile:
						#print fyle.RelativePath
						fname = fyle.RelativePath.strip()
						fname = fname.lower()
						#sys.stderr.write("\nfile: (%s)" % (fname))        
						if fname[-4:]==".xml":
							strFile=fyle.RelativePath
							xmlfilename = strFile
							if os.path.exists(os.path.join(xmlfolder, strFile))==False:
								sys.stderr.write('downloading ' + strFile + '\n')
								urllib.urlretrieve(xmlbaseurl + urllib.quote(strFile), os.path.join(xmlfolder, strFile))
								shutil.copyfile(os.path.join(xmlfolder, strFile), os.path.join('newfiles', strFile))
								return xmlfilename
							else: # file already there
								return xmlfilename
						# if you didn't find an xml file just return an empty string
					return ''
				except:
					return ''
		except:
			return ''
	except WebFault, e:
		sys.stderr.write("soap request failed: " + e)
		return ""



#build a search request object
TrainingComponentTypeFilter=client.factory.create('TrainingComponentTypeFilter')
TrainingComponentTypeFilter.IncludeTrainingPackage=True
TrainingComponentSearchRequest=client.factory.create('TrainingComponentSearchRequest')
TrainingComponentSearchRequest.Filter='Training and Education'    #eg Filter='Animal Care and Management' ie training package name or '' for all
TrainingComponentSearchRequest.IncludeDeleted=False
TrainingComponentSearchRequest.SearchTitle=True
TrainingComponentSearchRequest.SearchCode=False
TrainingComponentSearchRequest.TrainingComponentTypes=TrainingComponentTypeFilter

try:
   result = client.service.Search(TrainingComponentSearchRequest)

   for tp in result.Results[0]:
      xmlfilename = get_xml(tp.Code)

except WebFault, e:
   print "soap request failed:", e
