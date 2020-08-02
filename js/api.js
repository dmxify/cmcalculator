async function api_fetch_json(resource){

  // try get chart data (provided this user has the correct permissions)
    var options = {};
    options.resource = resource;

    const response = await fetch('api/index.php', {
        method: 'post',
        headers: {
          'Accept': 'application/json, text/plain, */*',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(options)
      });
      const json = await response.json();
      return json;
}
