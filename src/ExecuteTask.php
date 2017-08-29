<?php

namespace davidjeddy\AutoGourcer;

class ExecuteTask
{
    /**
     * ExecuteTask constructor.
     */
    public function __construct()
    {
        $data = $this->getRepoDate();
        //print_r($data);

//        foreach($data as $k => $repo) {
//            $data2[] = $this->cloneRepo($repo);
//        }
        //print_r($data2);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getRepoDate(): array
    {
        $returnData = [];
        $errorCode = 0;

        // function exec ($command, array &$output = null, &$return_var = null) {}
        //exec('curl -u david@sourcetoad.com:Asdf1234 https://api.bitbucket.org/1.0/user/repositories --compressed --output ./logs/repos.json  -vvv >> ./logs/curl.log', $responseData, $errorCode);

        if ($errorCode !== 0) { $this->throwError(__METHOD__, $errorCode); }

        $responseData = \json_decode(\file_get_contents('./logs/repos.json'));

        $repoCount = count($responseData);

        for ($i = 0; $i < $repoCount; $i++) {

            if ($i > 5) {
                break;
            }

            $returnData[] = [
                'name'      => $responseData[$i]->name,
                'logo'      => $responseData[$i]->logo,
                'clone_url' => 'https://David_Eddy@bitbucket.org/Sourcetoad/' . $responseData[$i]->name . '.git'
            ];

            print_r( $responseData );
            exit(1);
        }

        return $returnData;
    }

    /**
     * @param array $url
     * @return int
     */
    private function cloneRepo(array $paramData): int
    {
        exec("git clone {$paramData['clone_url']} ./repos/{$paramData['name']}  >> ./logs/git.log", $responseData, $errorCode);

        if ($errorCode !== 0) { $this->throwError(__METHOD__, $errorCode); }

        return $responseData;
    }

    /**
     * @param string $method
     * @param int $paramData
     * @throws \Exception
     */
    private function throwError(string $method, int $paramData)
    {
        throw new \Exception("Non-zero return code received from {$method}. Code " . $paramData);
    }
}

new ExecuteTask();
