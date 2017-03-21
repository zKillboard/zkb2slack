# zkb2slack

A simple command line utility that is executed via cron.

To install:

    git clone https://github.com/zKillboard/zkb2slack.git
    
To execute add this line to your cron:

````    * * * * * queueID="yourQueueID" slackHookURL="https://hooks.slack.com/services/your/hook/here" channel="#killmails" name="The Name Here" php /location/of/clone/zkb2slack.php````

The options are: 

* queueID is an ID you make up that will identify you
* slackHookURL is the hook that you will use to talk to your slack server
* channel is the channel that the slack server will publish the messages
* name is the name of your corporation or alliance, it must match exactly, including capitalization and any punctuation
