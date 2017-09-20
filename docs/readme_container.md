# PHP library 

## General Requirements

 - Shell / Terminal access: execute commands
 - [Docker](https://www.docker.com): library execution in isolation
 - [Git](https://git-scm.com/): library installation
 
## Installation

 - Execute the following commands in a shell:
  
    `cd {desired project root}`

    `git clone https://github.com/davidjeddy/auto-gourcer.git ./`

## Configuration

 - Edit ./run.php on line 35 with valid GiT credentials.
 !!!Changes to ./run.php are not tracked!!! So you do not need to worry about credentials leaking. However, We recommend
 usage of a dotenv library similar to [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) to handle credentials.
                                                                                                                   
 
## Usage

Start the service using docker compose

    `cd {project root}`

    `docker-composer up --build -d`

### Basic:

 - Usage is super simple running the library as a container service. 

    `docker exec -it AutoGourcer php ./run.php`

### Advanced:

***TODO***

## Output

Once the process has run successfully the result are available in the ./renders directory (by default).
