# PHP library 

## General Requirements

 - Shell / Terminal access: execute commands
 - [Docker](https://www.docker.com): library execution in isolation
 - [Git](https://git-scm.com/): library installation
 
## Installation

 - Execute the following commands in a shell:
  
    `cd {desired project root}`

    `git clone https://github.com/davidjeddy/auto-gourcer.git ./`

## Usage

Basic:

 - Usage is super simple running the library as a container service. 

    `docker exec auto_gourcer php ./src/run.php`

Advanced: ***TODO***

 - Copy the file `override.csv.dist` as `override.csv`, change settings as desired, run service.

    `docker exec auto_gourcer php ./src/run.php`

## Output

Once the process has run successfully the result are available in the ./renders directory (by default).
