#How to set up git remote on server for deployment

##Server Setup
1. ssh into server
2. make repo directory `mkdir gitrepos`
3. initialize repo `git init --bare`
4. create post-receive hook in repo dir `cd gitrepos && nano hooks/post-receive`
5. copy contents of post-receive script into file and save
6. add exec permissions to script `chmod +x hooks/post-receive`
7. make sure ssh key of local machine in `~/.ssh/authorized_keys`
8. if not, copy it onto server `pbcopy < ~/.ssh/id_rsa.pub` `ssh-copy-id -i ~/.ssh/mykey user@host`

##Local Setup
1. create blank `.dev` file. it is gitignored so it is only on local machine
2. add production remote `git remote add production u79293309@home555332537.1and1-data.host:gitrepos`
3. create pre-push hook in local repo dir `nano .git/hooks/pre-push`
4. copy contents of pre-push script into file and save
5. add exec permissions to script `chmod +x .git/hooks/pre-push`

##Deploy Changes
You have two options here.
###Merge Locally
1. once your new feature is ready to deploy, commit changes to `development branch`
2. switch to staging branch for testing `git checkout staging`
3. merge new feature into staging `git merge development`
4. push changes to origin staging while also triggering deploy script `git push origin staging`
2. switch to master branch `git checkout master`
3. merge new feature into master `git merge staging`
4. push changes to origin master while also triggering deploy script `git push origin master`
###Through GitLab Merge Request
1. Perform GitLab Merge Request like normal
2. update local master branch `git pull origin master`
3. deploy changes to production `git push production master`

####**If the "files" or "uploads" folders disappear, copy then from the "deployment" folder