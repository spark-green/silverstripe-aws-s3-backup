# Spark Green module for copying archives to AWS S3

## Authors
	* Julian Smith <julian@wubies.org>

## Overview
Transfer Silverstripe database and asset backups to your S3 bucket 

## Requirements 
	* SilverStripe 3.*
	* Silverstripe Backup (forked from DarrenInwood)

## Installation
composer require spark-green/silverstripe-aws-s3-backup dev-master

### Setup

You will need to create a new Amazon IAM user that has access to your S3 container.

If you don't have a user, you can use these instructions

1) Login to AWS console and go to IAM (Identity and Access Management_
2) Click on Users
3) Click on Create New Users
4) Add a user name (I'm using "s3-backup") and click Create
5) Click Show User Security Credentials and copy the Access Key and Secret Key into the Settings > AWSBackup tab in your Silverstripe installation

We now to give that user access to your S3 buckets

1) Go back to IAM Users and click on your new user
2) Go to Permissions and add the following permissions:

	s3:ListAllMyBuckets
	s3:GetBucketLocation
	s3:ListBucket

Lastly, go to S3 and create a bucket, and add that bucket name to the Settings > AWSBackup tab in your Silverstripe installation

You should now be able to test transferring files to your S3 bucket.

### Scheduling your backups

Add to your cron as the web user
	[WEBROOT]/framework/sake dev/tasks/S3Backup >> /tmp/S3Backup.log