#!/usr/bin/python

import pexpect
try:
	cmd = 'sftp -oPort=5022 eai_mvf@align.paramount.com'
	child = pexpect.spawn (cmd)
	child.expect("eai_mvf's password", timeout=10)	
	child.sendline('M@vF!51Sf')
	child.expect('sftp>')
	child.sendline('cd /home/eai_mvf/outbox/payload')
	child.expect('sftp>')
	child.sendline('get *.xml /home/kevin/test/paramount_meta')
	child.expect('sftp>')
	child.sendline('rm /home/eai_mvf/outbox/payload/*.xml')
	child.expect('sftp>')
	child.sendline('exit')
except Exception,e:
	print e
	
