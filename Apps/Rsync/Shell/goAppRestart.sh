#!/bin/bash

#====================================================
# Description: Simple logic for goApp serial restart.
# Author:
# Date: 2017.09.20
# Version: V1.0 Beta.
#====================================================

time_str=$(date "+%Y-%m-%d %H:%M:%S")
root_path="/search/odin/daemon/download"
Restart_script="${root_path}/restart.sh"
user="odin"
password="pVEBdH5z0ZVgKh1w8nR7"

function Usage()
{
	cat << EOF
Usage:
	sh $0 --help
	sh $0 \${servers[@]}
EOF
}

[ "$1" == "--help" -o "$#" == "0" ] && { Usage; exit 0; }

function preparation()
{
	if ! which sshpass >/dev/null 2>&1; then
		yum -y install sshpass.x86_64 1>/dev/null
	fi
}

function check()
{
	check_ip=$1
	ping -c 1 -w 2 ${check_ip} 2>&1
	return $?
}

function json_start()
{
	    cat << EOF
{
	"result": {
EOF
}

function json_end()
{
	    cat << EOF
	}
}
EOF
}

function showResult()
{
	Result_StatusCode=$1
	Result_IP=$2
	[[ "${Result_StatusCode}" == "0" ]] && sta="SUCCESS" || sta="FAIL"
	cat << EOF
		"${Result_IP}": {
			"status": "${sta}",
			"timeStamp": "${time_str}",
			"scriptFile": "${Restart_script}",
			"execStatusCode": "${Result_StatusCode}",
			"execOutput": "$(echo -n ${Result_echo})"
EOF
}

function restart()
{
	restart_ip=$1
	if sshpass -p "${password}" ssh -q -o StrictHostKeyChecking=no -o ConnectTimeout=10 ${user}@${restart_ip} "whoami" >/dev/null 2>&1; then
		sshpass -p "${password}" ssh -q -o StrictHostKeyChecking=no -o ConnectTimeout=10 ${user}@${restart_ip} "/bin/bash ${Restart_script} 2>&1"
		return $?
	else
		echo "User ${user}'s password is incorrect."
		return 99
	fi
}

function main()
{
	servers=$@
	sc=0
	preparation
	json_start
	for server in ${servers}
	do
		sc=$((${sc}+1))
		Result_echo=$(check ${server})
		check_res=$?
		if [[ "${check_res}" == "0" ]]; then
			Result_echo=$(restart ${server})
			restart_res=$?
			showResult "${restart_res}" "${server}"
			if [ "${sc}" -lt "$#" -a "${restart_res}" -eq "0" ]; then
				echo -e "\t\t},"
			else
				echo -e "\t\t}"
				break
			fi
		else
			showResult "${check_res}" "${server}"
			echo -e "\t\t}"
			break
		fi
	done
	json_end
}

main $@
