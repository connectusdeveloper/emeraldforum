@props(['tagsCloud'])

@once
  @push('scripts')
    <script src="https://unpkg.com/chart.js@4"></script>
    <script src="https://unpkg.com/chartjs-chart-wordcloud@4"></script>

    <script>
      if (screen.width >= 1024) {
        const tags = {{ str(json_encode($tagsCloud))->toHtmlString() }};
        Chart.defaults.color = window.localStorage.theme == 'dark' ? "#FFF" : "#000";
        Chart.defaults.font.weight = 600;
        Chart.defaults.font.family =
          "Nunito, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'";
        const ctx = document.getElementById("canvas");
        const ctxP = ctx.getContext("2d");
        new MutationObserver(([{
          oldValue
        }]) => {
          let newValue = document.documentElement.getAttribute('data-theme');
          if (newValue !== oldValue) {
            Chart.defaults.color = window.localStorage.theme == 'dark' ? "#FFF" : "#000";
            tagsCloud.update();
          }
        }).observe(document.documentElement, {
          attributeFilter: ['data-theme'],
          attributeOldValue: true
        });
        const tagsCloud = new Chart(ctxP, {
          type: "wordCloud",
          data: {
            labels: tags.map((d) => d.name), // {{-- labels: {{ str($tagsCloud->pluck('name'))->toHtmlString() }}, --}}
            datasets: [{
              label: "",
              hoverColor: "#1d4ed8",
              data: tags.map((d) => d.threads_count * 8), // {{-- data: {{ $tagsCloud->pluck('threads_count')->transform(fn($v) => $v * 20) }}, --}}
            }, ],
          },
          options: {
            title: {
              display: false,
              text: "Word Cloud",
            },
            layout: {
              padding: 10,
            },
            padding: 2,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                enabled: false,
              },
            },
          },
        });
        ctx.onclick = function(evt) {
          var points = tagsCloud.getElementsAtEventForMode(evt, "nearest", {
            intersect: true
          }, true);
          // console.log(points[0].index, tags[points[0].element.index].name, points);
          window.location.href = '{{ route('threads', ['tag' => '']) }}' + tags[points[0].index].name;
        };
      }
    </script>
  @endpush
@endonce

<div class="w-full max-w-sm min-h-[300px]">
  <canvas id="canvas"></canvas>
</div>
