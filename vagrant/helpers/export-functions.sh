#!/bin/bash

# run the bash script with the current bash environments
bashe() {
	sudo -E bash $1
}

start_script() {
	bashe "$SHELL_HOME/install/helpers/output-block.sh Script $1 started"
}

end_script() {
	bashe "$SHELL_HOME/install/helpers/output-block.sh Script $1 ended"
}

export -f bashe
export -f start_script
export -f end_script