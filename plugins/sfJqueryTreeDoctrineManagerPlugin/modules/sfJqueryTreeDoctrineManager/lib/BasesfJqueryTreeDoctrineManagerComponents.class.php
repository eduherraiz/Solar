<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfJqueryTreeDoctrineManager components.
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Gregory Schurgast <michkinn@gmail.com>
 * @author     Gordon Franke <info@nevalon.de>
 * @version    SVN: $Id: BasesfGuardAuthActions.class.php 7745 2008-03-05 11:05:33Z fabien $
 */
class BasesfJqueryTreeDoctrineManagerComponents extends sfComponents
{
  public function executeManager(){
		
		if ($this->records = $this->executeControl())
        {
            $this->hasManyRoots = $this->modelHasManyRoots();
			$request = $this->getRequest();
			
			if (  !$request->hasParameter('root') && !$this->modelHasManyRoots() )
            {
				$this->getController()->redirect(url_for(  $request->getParameter('module') . '/'. $request->getParameter('action') .'?root=1'), true);
				return sfView::NONE;
			}
			elseif ( !$request->hasParameter('root') && $this->modelHasManyRoots() )
            {
				$this->roots = $this->getRoots( $this->model );
			}
			else{
				$this->records = $this->getTree($this->model, $request->getParameter('root'));
			}
		}
	}

	/*
	 * return exception if Model is not defined as NestedSet
	*/
	private function executeControl(){
		if ( ! Doctrine_Core::getTable($this->model)->isTree() )
        {
			throw new Exception('Model "'.$this->model.'" is not a NestedSet');
			return false;
		}
		return true;		
	}
	
	
	/*
	* Returns the roots
	*/
	private function getRoots($model){
        $tree = Doctrine_Core::getTable($model)->getTree();
        return $tree->fetchRoots();
    }
	
    private function getTree($model, $rootId = null){
        $tree = Doctrine_Core::getTable($model)->getTree();
        $options = array();
        if($rootId !== null)
        {
            $options['root_id'] = $rootId;
        }
        return $tree->fetchTree($options);
    }

	private function modelIsNestedSet(){
		return $this->options['treeImpl'] == 'NestedSet';
	}
	
	private function modelHasManyRoots(){
		$template = Doctrine_Core::getTable($this->model)->getTemplate('NestedSet');
        $options = $template->option('treeOptions');
        return isset($options['hasManyRoots']) && $options['hasManyRoots'];
	}
}
