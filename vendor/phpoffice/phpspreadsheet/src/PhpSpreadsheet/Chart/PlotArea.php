<?php

namespace PhpOffice\PhpSpreadsheet\Chart;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

//Roland Finke: Multiple Changes made to integrate secondary Yaxis

class PlotArea
{
    /**
     * PlotArea Layout.
     *
     * @var Layout
     */
    private $layout;

    /**
     * Plot Series.
     *
     * @var DataSeries[]
     */
    private $plotSeries = [];
    
    /**
     * Secondary Plot Series.
     *
     * @var DataSeries[]
     */
    private $secondaryYAxisPlotSeries  = [];


    /**
     * Create a new PlotArea.
     *
     * @param null|Layout $layout
     * @param DataSeries[] $plotSeries
     */
    public function __construct(Layout $layout = null, array $plotSeries = [], array $secondaryYAxisPlotSeries  = [] )
    {
        $this->layout = $layout;
        $this->plotSeries = $plotSeries;
        $this->secondaryYAxisPlotSeries = $secondaryYAxisPlotSeries;
    }

    /**
     * Get Layout.
     *
     * @return Layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Get Number of Plot Groups.
     *
     * @return array of DataSeries
     */
    public function getPlotGroupCount()
    {
        return count($this->plotSeries);
    }
    
    /**
     * Get Number of Plot Secondary Groups.
     *
     * @return array of DataSeries
     */
    public function getPlotSecondaryGroupCount()
    {
        return count($this->secondaryYAxisPlotSeries);
    }

    /**
     * Get Number of Plot Series.
     *
     * @return int
     */
    public function getPlotSeriesCount()
    {
        $seriesCount = 0;
        foreach ($this->plotSeries as $plot) {
            $seriesCount += $plot->getPlotSeriesCount();
        }

        return $seriesCount;
    }
    
    /**
     * Get Number of Plot Secondary Series.
     *
     * @return int
     */
    public function getPlotSecondarySeriesCount()
    {
        $seriesCount = 0;
        foreach ($this->secondaryYAxisPlotSeries  as $plot) {
            $seriesCount += $plot->getPlotSecondarySeriesCount();
        }

        return $seriesCount;
    }


    /**
     * Get Plot Series.
     *
     * @return array of DataSeries
     */
    public function getPlotGroup()
    {
        return $this->plotSeries;
    }
    
    /**
     * Get Plot Secondary Series.
     *
     * @return array of DataSeries
     */
    public function getPlotSecondaryGroup()
    {
        return $this->secondaryYAxisPlotSeries;
    }

    /**
     * Get Plot Series by Index.
     *
     * @param mixed $index
     *
     * @return DataSeries
     */
    public function getPlotGroupByIndex($index)
    {
        return $this->plotSeries[$index];
    }
    
    /**
     * Get Plot Secondary Series by Index.
     *
     * @param mixed $index
     *
     * @return DataSeries
     */
    public function getPlotSecondaryGroupByIndex($index)
    {
        return $this->secondaryYAxisPlotSeries[$index];
    }

    /**
     * Set Plot Series.
     *
     * @param DataSeries[] $plotSeries
     *
     * @return PlotArea
     */
    public function setPlotSeries(array $plotSeries)
    {
        $this->plotSeries = $plotSeries;

        return $this;
    }
    
    /**
     * Set Plot Secondary Series.
     *
     * @param DataSeries[] $plotSeries
     *
     * @return PlotArea
     */
    public function setPlotSecondarySeries(array $plotSeries)
    {
        $this->secondaryYAxisPlotSeries  = $plotSeries;

        return $this;
    }

    public function refresh(Worksheet $worksheet)
    {
        foreach ($this->plotSeries as $plotSeries) {
            $plotSeries->refresh($worksheet);
        }
        
        if(count($this->secondaryYAxisPlotSeries) > 0)
        {
            foreach($this->secondaryYAxisPlotSeries as $plotSeries) {
                $plotSeries->refresh($worksheet);
            }
        }
    }
}
