#!/usr/bin/python

from apscheduler.scheduler import Scheduler
import logging;logging.basicConfig()
import os
import time
import sys

sched = Scheduler()
sched.start()

def _process():

	os.system("/home/kevin/test/paramount_meta/update.php")
	print "done"


job = sched.add_cron_job(_process, hour='8', minute='24', second='0')


while True:
    try:
        time.sleep(3600)
    except KeyboardInterrupt:
        sched.shutdown(wait=True, shutdown_threadpool=True, close_jobstores=True)
        sys.exit()
